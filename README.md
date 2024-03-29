## Design patterns that have been used:
When designing and implementing system, I will at minimum adhere to TDD, SOLID and DRY.

### MVC
Used to separate applications concerns. i.e Controllers / Model / Views. i.e Laravel, Zend etc.

### Facade
Using a facade helps to remove complex subsystems from the client caller.
Used here to the hide the complex subsystem of converting data from CSV and sorting and any other related funtionalities that may appear in the future.

### Other considerations
- Utilise file input reader interface to ensure we can switch out to a different file read type if required i.e text, pdf or word etc.
- Utilise transformer interface to ensure we can transform different format to the BankTrasnactionService requires when creating BankTransaction object (model).
- Facade to hide complexities from the Controller.
- I decided not to make use of ReactJS for front end components as HTML views for table structures is suitable. However, with more details, we may look for this approach.
- Discussion on other requirements will enable us to further improve the solution.

### Setup
1. Git clone project: git clone https://github.com/joshkour/csv_upload.git
2. Run in root path: composer install
3. Create apache vhost to point to directory (<PATH_TO_PROJECT>/public)
4. Go to http://127.0.0.1 (if set up as localhost)


