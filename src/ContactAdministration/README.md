# Contact Administration

Generic context to manage contact information. Contacts are organized in `ContactCard`s.
A `ContactCard` can either reference a person or a company. Each `ContactCard` can have a `BankAccount` assigned.

## Importing Contacts

You can find test contacts in `import/contacts.json`. The data can be imported using the script `bin/import.php`.
Run the script with the following command:

```bash
docker-compose run php php src/ContactAdministration/bin/import.php
```
