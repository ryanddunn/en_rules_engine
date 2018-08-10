# Evernote Rules Engine
This project is a *Rules Engine* for Evernote using their published API.

By using the term *Rules Engine* I'm implying that you can create basic search queries
and bind simple actions to those queries.

### Example of a Rule
Search for the term "Apple" in a specific **EN-Notebook**, if any results are returned then apply the **EN-Tag** "Fruit" to those notes.

### Feature Backlog
Below is a rough list of features I'm working on. These features completely driven by my own opinion and used to keep me organized. I'm happy to accept some input if you would like to help with the project.

1. timestamp when action occurs include note title and guid
2. form inserts need SQL injection protection
3. user id is passed via GET var in plain text, will need security
4. need logging, db, table structure (each cron job run,
    each rule, en note guid) so we can track any rule on any note per execution run
5. search terms that support both AND and OR options, currently singular term matching
6. when managing search terms / rules, need ability to match exact set or any word in search phrase
    this is just done by removing the quote in the notefilter string
7. remove the "dev" toggle in code, instead have the "rules_engine.php" call the DB and have the DEV toggle
    exist in the DB at the user account level
8. change "rules_engine.php" to loop through any "active" user, not be hard coded to a single user.
9. [DONE] update directory references to support crontab functionality
10. apply logging reports to mgr.php UI, create Log web page for research and reporting
