## Description
Provide a brief overview of what this PR introduces, changes, or removes, explaining the technical
reason behind these modification.

## 🔧 What was done
List the main changes made the code in bullet points (e.g., file creation, refactoring, new dependencies,
or configuration adjustments).

## 🧪 How to test
Step-by-step instructions for the reviewer to test the changes (e.g., commands to run, tests to be performed,
specific endpoints to call, or UI flows to follow).

1. Setup the environment
~~~bash
# Start development environment
docker compose --profile web up -d --build
~~~

~~~bash 
# Start testing environment
docker compose --profile test --env-file=.env.testing up -d --build
~~~

2. Run the test suite to verify all changes
~~~bash
docker compose exec app_test php artisan test
~~~

## 📘  Docs
API documentation can be viewed locally at:
http://localhost/docs/api#/

## 🔗 Related Issue
Closes #**ISSUE_NUMBER**