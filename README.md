# GitInsight CLI

GitInsight is a powerful command-line tool built with Laravel that provides in-depth analysis and insights for Git repositories. It offers valuable information about commit history, code quality, and team collaboration patterns.

## Features

- **Commit Analysis**: Total commits, top contributors, and commit frequency over time.
- **Code Quality Metrics**: File count, total lines of code, and a simple complexity score.
- **Collaboration Insights**: Collaboration score and top collaborating pairs of authors.

## Requirements

- PHP 8.1 or higher
- Composer
- Git

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/yourusername/git-insight.git
   cd git-insight
   ```

2. Install dependencies:

   ```bash
   composer install
   ```

3. Copy the `.env.example` file to `.env` and configure your environment variables:

   ```bash
   cp .env.example .env
   ```

4. Generate an application key:

   ```bash
   php artisan key:generate
   ```

## Usage

To analyze a Git repository, use the following command:

```bash
php artisan git-insight:analyze {path}
```

Replace `{path}` with either a local path to a Git repository or a remote Git repository URL.

Examples:

1. Analyze a local repository:
   ```bash
   php artisan git-insight:analyze /path/to/local/repo
   ```

2. Analyze a remote repository:
   ```bash
   php artisan git-insight:analyze https://github.com/laravel/framework.git
   ```

## Output

The tool will provide a detailed analysis including:

- Commit insights (total commits, top contributors, commit frequency)
- Code quality metrics (file count, lines of code, complexity score)
- Collaboration insights (collaboration score, top collaborating pairs)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Testing

To run the tests, use the following command:

```bash
php artisan test
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

If you encounter any problems or have any questions, please open an issue on the GitHub repository.

## Acknowledgements

- [Laravel](https://laravel.com)
- [Symfony Process](https://symfony.com/doc/current/components/process.html)

---

Built with ❤️ using Laravel
