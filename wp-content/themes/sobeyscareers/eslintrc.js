module.exports = {
    "env": {
        "browser": true,
        "jquery": true,
        "es6": true,
    },
    "extends": "wordpress",
    "plugins": [],
    "rules": {
        // Enforce spacing inside array brackets
        'array-bracket-spacing': ['error', 'always'],
        // Enforce one true brace style
        'brace-style': 'error',
        // Require camel case names
        'camelcase': ['off', {
            properties: 'always'
        }],
        // Disallow or enforce trailing commas
        'comma-dangle': ['error', 'never'],
        // Enforce spacing before and after comma
        'comma-spacing': 'error',
        // Enforce one true comma style
        'comma-style': ['error', 'last'],
        // Encourages use of dot notation whenever possible
        'dot-notation': ['error', {
            allowKeywords: true,
            allowPattern: '^[a-z]+(_[a-z]+)+$'
        }],
        // Enforce newline at the end of file, with no multiple empty lines
        'eol-last': 'error',
        // Require or disallow spacing between function identifiers and their invocations
        'func-call-spacing': 'off',
        // Enforces spacing between keys and values in object literal properties
        'key-spacing': ['error', {
            beforeColon: false,
            afterColon: true
        }],
        // Enforce spacing before and after keywords
        'keyword-spacing': 'error',
        // Disallow mixed "LF" and "CRLF" as linebreaks
        'linebreak-style': ['error', 'unix'],
        // Enforces empty lines around comments
        'lines-around-comment': ['error', {
            beforeLineComment: true
        }],
        // Disallow mixed spaces and tabs for indentation
        'no-mixed-spaces-and-tabs': 'error',
        // Disallow use of multiline strings
        'no-multi-str': 'error',
        // Disallow multiple empty lines
        'no-multiple-empty-lines': 'error',
        // Disallow use of the with statement
        'no-with': 'error',
        // Require or disallow an newline around variable declarations
        'one-var-declaration-per-line': ['error', 'initializations'],
        // Enforce operators to be placed before or after line breaks
        'operator-linebreak': ['error', 'after'],
        // Require or disallow use of semicolons instead of ASI
        'semi': ['error', 'always'],
        // Require or disallow space before blocks
        'space-before-blocks': ['error', 'always'],
        // Require or disallow space before function opening parenthesis
        'space-before-function-paren': ['error', 'never'],
        // Require or disallow space before blocks
        'space-in-parens': ['error', 'never', { exceptions: ['{}', '[]', '()'] }],
        // Require spaces around operators
        'space-infix-ops': 'error',
        // Require or disallow spaces before/after unary operators (words on by default, nonwords)
        'space-unary-ops': ['error', {
            overrides: { '!': true }
        }],
        // Requires to declare all vars on top of their containing scope
        'vars-on-top': 0,
        // Require or disallow Yoda conditions
        'yoda': ['error', 'always']
    }
}
