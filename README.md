# What is Task CLI

This is an application that can run from the command line, accept user actions and inputs as argument and store the tasks in a JSON file.

# Installation

```bash
git clone https://github.com/aung-myint-myat-dev/php-task-cli.git

cd task-cli
```

```bash
// Linux

chmod +x bin/task-cli

sudo ln -s "$(pwd)/bin/task-cli" /usr/local/bin/task-cli
```

```
// Window

* Search Enviroment Variable in Windows Search bar.
* Open Edit the system environment variables
* Click Environment Variables
* Select the path from User variables
* Click Edit → New
* Enter your project bin folder path.

eg: C:\Users\YourName\Desktop\task-cli\bin

* Click Okay and Close All Windows.

* Now you can run 

task-cli.bat add "Learn PHP"

task-cli.bat list

task-cli.bat delete 1
```

# Features

* Add, Update and Delete tasks
* Mark a task as in progress or done
* List all tasks
* List all tasks that are complete
* List all tasks that are not complete
* List all tasks that are in progress

# List Of Commands

### Adding new task

```bash
task-cli add "Your task write here."
```

### Updating and deleting tasks

```bash
task-cli update 1 "New update data write here."
```

### Marking a task as in done

```bash
task-cli mark-as-done 1(Your Task Id)
```

### Listing all tasks

```bash
task-cli list
```

### Listing tasks by status

```bash
task-cli list done
task-cli list todo
task-cli list complete
task-cli list in-progress
```