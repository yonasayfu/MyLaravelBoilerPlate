# 🚀 Laravel Boost and MCP Usage Guide

This guide explains how to use Laravel Boost and MCP (Model Context Protocol) with your Laravel boilerplate project.

## 🎯 What is Laravel Boost and MCP?

**Laravel Boost** is an official Laravel package that provides AI development tools including:
- Database schema analysis
- Laravel documentation search
- Code execution with Tinker
- Browser logs integration

**MCP (Model Context Protocol)** allows AI assistants to understand your specific project patterns and architecture.

## 🚀 Getting Started

### 1. Start the MCP Server

```bash
php artisan boost:mcp
```

This starts the MCP server on `http://localhost:8090`.

### 2. Connect Your AI Assistant

Connect your AI assistant (Claude, Cursor, etc.) to the MCP server:
- URL: `http://localhost:8090`
- The AI will now understand your project's architecture and patterns

### 3. Use Built-in Laravel Boost Tools

Ask questions like:
- "Show me the database schema"
- "What Laravel version is this using?"
- "List all routes in the application"
- "Explain the user authentication system"

### 4. Use Custom MCP Tools

Your project includes custom MCP tools:
- `analyze_boilerplate_architecture` - Check Clean Architecture compliance
- `list_boilerplate_models` - List all Eloquent models
- `check_database_tables` - Inspect database schema
- `get_boilerplate_statistics` - Get project statistics

Ask questions like:
- "What models are in this boilerplate?"
- "Analyze the architecture compliance"
- "Check the users table structure"

## 🧪 Testing MCP Integration

Run the MCP discovery command to register all tools:

```bash
php artisan mcp:discover
```

List available tools:

```bash
php artisan mcp:list
```

## 🎯 Benefits

1. **AI Understanding**: Your AI assistant understands your exact code patterns
2. **Architecture Compliance**: Automatic checking of Clean Architecture implementation
3. **Performance Analysis**: Database and code performance suggestions
4. **Code Generation**: AI generates code following YOUR exact patterns
5. **Documentation**: Project architecture is queryable and self-documenting

## 🚀 Best Practices

1. **Run MCP Analysis After Major Changes**: After implementing new modules, run `php artisan boost:mcp` to analyze the changes
2. **Use AI for Code Review**: Ask AI to review your code for architecture compliance
3. **Leverage Documentation**: Use MCP tools to understand existing code when making changes
4. **Test Integration**: Ensure new features work well with MCP tools

## 🎓 Learning Objectives

- Understand how AI can assist in Laravel development
- Learn to leverage project-specific AI tools
- Master architecture compliance checking
- Utilize performance analysis tools