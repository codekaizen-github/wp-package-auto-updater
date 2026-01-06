# wp-package-autoupdater

## Debugging with Xdebug

Start the debugger under your editor's "Run and Debug" section with the following configuration.

To debug the application using Xdebug, you can use the following wrapper command:

```bash
composer run debug <composer-script> [args...]
```

Examples:

```bash
composer run debug test
composer run debug analyze
composer run debug sniff
```
