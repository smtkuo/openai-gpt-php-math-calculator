# Openai Gpt Php Math Calculator 

This project is a PHP application that performs secure and accurate mathematical calculations using OpenAI's GPT-3.5-turbo model.

## Features

- Securely evaluates mathematical expressions
- Correctly applies order of operations (PEMDAS)
- Cleans and validates user input
- Displays results with precision up to 6 decimal places

## Installation

1. Clone the project:   ```
   git clone https://github.com/smtkuo/openai-gpt-php-math-calculator.git   ```

2. Install the required dependencies:   ```
   composer install   ```

3. Create a `.env` file and add your OpenAI API key:   ```
   OPENAI_API_KEY=your_api_key_here   ```

## Usage

Run the application on a PHP server and send GET requests to the `case.php` file. Specify the mathematical expression using the `input` parameter.

### Example Usage

1. Simple multiplication and division:   ```
   /case.php?input=3x6/2-18   ```
   Example Response: -9.000000

2. Expression with parentheses:   ```
   /case.php?input=(4+5)x3-7   ```
   Example Response: 20.000000

3. Complex expression:   ```
   /case.php?input=10/2+3x4-5   ```
   Example Response: 12.000000

4. Decimal numbers:   ```
   /case.php?input=3.5x2+4.2   ```
   Example Response: 11.200000

5. Negative numbers:   ```
   /case.php?input=-5+3x2   ```
   Example Response: 1.000000

## Security

This application cleans and validates user input. It only accepts and evaluates valid mathematical expressions. However, it is recommended to implement additional security measures before using it in a production environment.
