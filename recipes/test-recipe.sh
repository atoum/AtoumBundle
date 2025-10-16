#!/bin/bash

#
# Automated Recipe Testing Script
# This script creates a test Symfony project and validates the recipe installation
#

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BUNDLE_DIR="$(dirname "$SCRIPT_DIR")"
TEST_DIR="/tmp/atoum-bundle-recipe-test-$(date +%s)"
RECIPE_VERSION="${1:-3.0}"

echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}AtoumBundle Recipe Testing Script${NC}"
echo -e "${BLUE}================================================${NC}"
echo ""
echo -e "Bundle directory: ${GREEN}$BUNDLE_DIR${NC}"
echo -e "Test directory: ${GREEN}$TEST_DIR${NC}"
echo -e "Recipe version: ${GREEN}$RECIPE_VERSION${NC}"
echo ""

# Function to print status
print_status() {
    echo -e "${BLUE}➜${NC} $1"
}

print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

# Check prerequisites
print_status "Checking prerequisites..."

if ! command -v symfony &> /dev/null; then
    print_error "Symfony CLI not found. Please install it first:"
    echo "  curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash"
    echo "  sudo apt install symfony-cli"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    print_error "Composer not found. Please install it first."
    exit 1
fi

if ! command -v php &> /dev/null; then
    print_error "PHP not found. Please install it first."
    exit 1
fi

PHP_VERSION=$(php -r 'echo PHP_VERSION;')
print_success "Prerequisites OK (PHP $PHP_VERSION)"

# Create test project
print_status "Creating test Symfony project..."
symfony new "$TEST_DIR" --webapp --php=$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')
cd "$TEST_DIR"
print_success "Test project created"

# Configure Composer for local recipe
print_status "Configuring local recipe endpoint..."
composer config repositories.atoum-bundle-package path "$BUNDLE_DIR"
composer config extra.symfony.endpoint "[\"file://$BUNDLE_DIR/recipes\", \"flex://defaults\"]" --json
composer config extra.symfony.allow-contrib true
print_success "Recipe endpoint configured"

# Install the bundle
print_status "Installing atoum-bundle with recipe..."
composer require --dev atoum/atoum-bundle:@dev -vvv --no-interaction
print_success "Bundle installed"

# Validation
print_status "Validating recipe installation..."

ERRORS=0

# Check bundle registration
print_status "Checking bundle registration..."
if grep -q "AtoumAtoumBundle" config/bundles.php; then
    if grep -q "'dev' => true, 'test' => true" config/bundles.php; then
        print_success "Bundle registered correctly in dev and test environments"
    else
        print_error "Bundle registered but not in correct environments"
        ERRORS=$((ERRORS + 1))
    fi
else
    print_error "Bundle not registered in config/bundles.php"
    ERRORS=$((ERRORS + 1))
fi

# Check configuration file
print_status "Checking configuration file..."
if [ -f "config/packages/test/atoum.yaml" ]; then
    print_success "Configuration file created: config/packages/test/atoum.yaml"
    
    # Validate YAML syntax
    if php bin/console lint:yaml config/packages/test/atoum.yaml &> /dev/null; then
        print_success "Configuration file has valid YAML syntax"
    else
        print_error "Configuration file has invalid YAML syntax"
        ERRORS=$((ERRORS + 1))
    fi
else
    print_error "Configuration file not created"
    ERRORS=$((ERRORS + 1))
fi

# Check .atoum.php
print_status "Checking .atoum.php file..."
if [ -f ".atoum.php" ]; then
    print_success ".atoum.php file created"
    
    # Check PHP syntax
    if php -l .atoum.php &> /dev/null; then
        print_success ".atoum.php has valid PHP syntax"
    else
        print_error ".atoum.php has invalid PHP syntax"
        ERRORS=$((ERRORS + 1))
    fi
else
    print_error ".atoum.php file not created"
    ERRORS=$((ERRORS + 1))
fi

# Check .gitignore
print_status "Checking .gitignore..."
if grep -q ".atoum.php" .gitignore; then
    print_success ".atoum.php added to .gitignore"
else
    print_warning ".atoum.php not added to .gitignore (optional)"
fi

# Check command availability
print_status "Checking atoum command..."
if php bin/console list atoum &> /dev/null; then
    print_success "atoum command is available"
else
    print_error "atoum command not available"
    ERRORS=$((ERRORS + 1))
fi

# Create a test file
print_status "Creating a sample test..."
mkdir -p tests/Units
cat > tests/Units/ExampleTest.php << 'EOF'
<?php

namespace App\Tests\Units;

use atoum\AtoumBundle\Test\Units\Test;

class ExampleTest extends Test
{
    public function testExample()
    {
        $this
            ->boolean(true)
                ->isTrue()
            ->string('atoum')
                ->isNotEmpty()
                ->hasLength(5)
        ;
    }
}
EOF
print_success "Sample test created"

# Run the test
print_status "Running sample test..."
if php bin/console atoum --directory=tests --no-code-coverage; then
    print_success "Test executed successfully"
else
    print_error "Test execution failed"
    ERRORS=$((ERRORS + 1))
fi

# Summary
echo ""
echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}Test Summary${NC}"
echo -e "${BLUE}================================================${NC}"

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✓ All checks passed!${NC}"
    echo -e "${GREEN}✓ Recipe installation successful${NC}"
    echo ""
    echo "Test project location: $TEST_DIR"
    echo ""
    echo "To explore the test project:"
    echo "  cd $TEST_DIR"
    echo "  php bin/console atoum --directory=tests"
    EXIT_CODE=0
else
    echo -e "${RED}✗ $ERRORS check(s) failed${NC}"
    echo -e "${RED}✗ Recipe installation has issues${NC}"
    echo ""
    echo "Test project preserved at: $TEST_DIR"
    echo "Please review the errors above and fix the recipe."
    EXIT_CODE=1
fi

echo ""
echo -e "${BLUE}================================================${NC}"

# Cleanup option
if [ $ERRORS -eq 0 ]; then
    read -p "Delete test project? (y/N) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        rm -rf "$TEST_DIR"
        print_success "Test project deleted"
    fi
fi

exit $EXIT_CODE


