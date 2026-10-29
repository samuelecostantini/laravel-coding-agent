# Laravel Coding Agent

A powerful AI-powered coding assistant built for Laravel applications, designed to enhance developer productivity through intelligent code generation and assistance.

## Features

- **AI-Powered Code Generation**: Generate code snippets, classes, controllers, and more using advanced AI models
- **Conversation-Based Development**: Engage in natural language conversations with the AI to build complex features
- **Tool Integration**: Seamless integration with various development tools and frameworks
- **Database Migration Support**: Easy database schema management with Laravel migrations

## Installation

1. Install the package via Composer:
```bash
composer require laravel/ai
```

2. Publish the configuration:
```bash
php artisan vendor:publish --provider="Laravel\Ai\AiServiceProvider"
```

3. Run migrations to set up the database schema:
```bash
php artisan migrate
```

## Usage

### Starting a Conversation

Begin a coding session by starting a conversation with the AI assistant:
```bash
php artisan ai:chat
```

### Generating Code

Use natural language prompts to generate code:
- "Create a new controller for user management"
- "Generate a migration for a products table with foreign keys"
- "Write a service class for handling user authentication"

### Tool Integration

The agent supports various tools including:
- Database operations (migrations, queries)
- File generation and modification
- Code refactoring
- API endpoint creation

## Migration Notes

This project includes custom database migrations that make tool-related attributes nullable in the conversation messages table. This allows for more flexible handling of AI-generated content without requiring all tool fields to be populated.

## Contributing

Contributions are welcome! Please see the [CONTRIBUTING](CONTRIBUTING.md) file for more information.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).