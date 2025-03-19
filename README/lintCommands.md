```bash
 #GitBash:
 find . -name "*.php" -exec php -l {} \;

#PowerShell
Get-ChildItem -Path . -Filter "*.php" -Recurse | ForEach-Object { php -l $_.FullName }
```
