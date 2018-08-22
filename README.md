# Evernote Rules Engine
This project is a *Rules Engine* for Evernote using their published API. The application is built in a LAMP Stack with a cron job running the automated *Rules Engine*. Currently using web forms, future version I hope to move to a traditional Service Oriented Architecture.

By using the term *Rules Engine* I'm implying that you can create basic search queries
and bind simple actions to those queries.

### Example of a Rule
Search for the term "Apple" in a specific **EN-Notebook**, if any results are returned then apply the **EN-Tag** "Fruit" to those notes.

### Feature Backlog
Below is a rough list of features I'm working on. These features completely driven by my own opinion and used to keep me organized. I'm happy to accept some input if you would like to help with the project.

* Move users and config to DB, then change "rules_engine.php" to loop through any "active" user, not be hard coded to a single user
** Possibly create a DEV and PROD instance of the rule-engine to run independently to support continuous integration
* Configure Default Notebook at the User Level for both "processed" and "un-processed" notes, this will address performance & re-processing concerns
* Move USER_ID to session variable
* Form inserts need SQL injection protection
* User id is passed via GET var in plain text, will need security
* Search terms that support both AND and OR options, currently singular term matching
* When managing search terms / rules, need ability to match exact set or any word in search phrase
    this is just done by removing the quote in the notefilter string
* remove the "dev" toggle in code, instead have the "rules_engine.php" call the DB and have the DEV toggle exist in the DB at the user account level


### Completed Features
* Apply logging reports to mgr.php UI, create Log web page for research and reporting
* Add ability to add more tags to existing notes
* Move Notebook selection to the *Rules* level as a column, remove it as an action. Apply all CRUD related operations to feature
* Update directory references to support crontab functionality\
* Need logging, db, table structure (each cron job run,
    each rule, en note guid) so we can track any rule on any note per execution run
* Timestamp when action occurs include note title and guid
