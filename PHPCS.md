# How to run code style checks (InPeace)

Run the linters locally:

- Install dev deps: `composer install --dev`
- Run PHP-CS-Fixer (dry-run): `composer cs:check`
- Run PHP-CS-Fixer (apply): `composer cs:fix`
- Run PHPCS (PSR-12 + Slevomat): `composer phpcs:check`
- Auto-fix PHPCS with PHPCBF: `composer phpcbf:fix`

CI should run `composer analyze && composer phpcs:check && composer cs:check && composer test` on PRs.
