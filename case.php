<?php
require 'vendor/autoload.php';

function loadEnvironment() {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

function validateAndCleanInput($input) {
    if (empty($input)) {
        throw new InvalidArgumentException("Input is empty");
    }
    // Replace 'x' with '*' for multiplication
    $input = str_replace('x', '*', $input);
    // Remove all whitespace
    $input = preg_replace('/\s+/', '', $input);
    // Check for valid characters after cleaning
    if (!preg_match('/^[\d\+\-\*\/\(\)\.]+$/', $input)) {
        throw new InvalidArgumentException("Input contains invalid characters");
    }
    return $input;
}

function getParenthesizedExpression($input) {
    $client = OpenAI::client($_ENV['OPENAI_API_KEY']);

    $response = $client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a precise calculator. Follow these rules strictly:
1. Use PEMDAS (Parentheses, Exponents, Multiplication and Division from left to right, Addition and Subtraction from left to right).
2. For expressions without parentheses, always perform multiplication and division from left to right before any addition or subtraction.
3. Convert the input expression to a fully parenthesized form that explicitly shows the order of operations.
4. Respond ONLY with the fully parenthesized expression, without any additional text or calculations.
Example: For "3*6/2-10", respond with "((3 * 6) / 2) - 10"'],
            ['role' => 'user', 'content' => "Convert this expression to a fully parenthesized form: $input"],
        ],
        'temperature' => 0,
    ]);

    return trim($response->choices[0]->message->content);
}

function evaluateExpression($expression) {
    $result = @eval("return $expression;");
    if ($result === false && error_get_last() !== null) {
        throw new RuntimeException("Error evaluating expression");
    }
    return $result;
}

function formatResult($result) {
    if (!is_numeric($result)) {
        throw new RuntimeException("Invalid calculation result");
    }
    return number_format($result, 6, '.', '');
}

function calculateExpression($input) {
    try {
        $cleanedInput = validateAndCleanInput($input);
        $parenthesizedExpression = getParenthesizedExpression($cleanedInput);
        $result = evaluateExpression($parenthesizedExpression);
        return formatResult($result);
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}

// Main execution
loadEnvironment();

$input = $_GET['input'] ?? '';
echo calculateExpression($input);
