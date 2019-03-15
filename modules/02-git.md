# Source Control Management



## Git Basics

You may have heard of SVN or subversion. Git is different from it in a way that Git stores `snapshots` instead of `changes from a base file`.

See Figure below demonstrating `changes from a base file`

![fig-ext 1 Subversion](https://git-scm.com/figures/18333fig0104-tn.png)

VS how Git behaves, which is recording `snapshots`:

![fig-ext 2 Git Snapshots](https://git-scm.com/figures/18333fig0105-tn.png)

## Git's 3 States

__This is the main thing to remember about Git if you want the rest of your learning process to go smoothly__. Git has three main states that your files can reside in: committed, modified, and staged. Committed means that the data is safely stored in your local database. Modified means that you have changed the file but have not committed it to your database yet. Staged means that you have marked a modified file in its current version to go into your next commit snapshot.

This leads us to the three main sections of a Git project: the Git directory, the working directory, and the staging area.

![fig-ext 3 Git States](https://git-scm.com/figures/18333fig0106-tn.png)

The Git directory is where Git stores the metadata and object database for your project. This is the most important part of Git, and it is what is copied when you clone a repository from another computer.

The working directory is a single checkout of one version of the project. These files are pulled out of the compressed database in the Git directory and placed on disk for you to use or modify.

The staging area is a simple file, generally contained in your Git directory, that stores information about what will go into your next commit. It’s sometimes referred to as the index, but it’s becoming standard to refer to it as the staging area.

The basic Git workflow goes something like this:

- You modify files in your working directory.
- You stage the files, adding snapshots of them to your staging area.
- You do a commit, which takes the files as they are in the staging area and stores that snapshot permanently to your Git directory.

If a particular version of a file is in the Git directory, it’s considered committed. If it’s modified but has been added to the staging area, it is staged. And if it was changed since it was checked out but has not been staged, it is modified. In Chapter 2, you’ll learn more about these states and how you can either take advantage of them or skip the staged part entirely.

## Activity: Git In Action

### Step 1

Create a folder called `hello-git` anywhere you like then open up your git bash (right-click then `Git Bash Here`) or your terminal.

To create a `Git` repository:
```bash
git init
```

### Step 2

Create any file you want to track. In this excercise, you may create a file called `octocat.txt` and open it in any editor you want.

Check `Git`'s status with:
```bash
git status
```

Which should output something like:

```
On branch master

Initial commit

Untracked files:
  (use "git add <file>..." to include in what will be committed)

        octocat.txt

nothing added to commit but untracked files present (use "git add" to track)
```

We now have an `unstaged` file in our `working directory` (review our 3 states).

Tip: remember this command (`git status`) as you'll do this a lot.

### Step 3

Now let's put our files to the `staging area` of git by adding it using:

```bash
git add octocat.txt
```

This should add the file in our `staging area`.

To check the status, do a `git status` again and it should output:
```
On branch master

Initial commit

Changes to be committed:
  (use "git rm --cached <file>..." to unstage)

        new file:   octocat.txt
```

__Other ways you can add files__:

You can also add `all` files with:
```bash
git add .
```

Or if you have folder with lots of contents, add the folder with:
```bash
git add my-folder
```
This will add the folder and all the files inside it (or changes in files inside it) to the `staging area`.

### Step 4

Now that the files are in the staging area, we can now `commit` the files so that they are recorded in git with:

```bash
git commit -m "Add file octocat.txt"
```

Where `-m ` option indicates that we'll follow up with a short message. We can also commit a long message with just `git commit` which will open an editor in your terminal.

Please don't panic when this opens, there are instructions below.
If you don't it probably opened `Vim`.

#### Vim

When in `Vim`, you can write when you are in `insert` mode which is triggered by pressing `insert`. Do that and type your comment.

To exit `Vim`, press `ESC` and type `:wq` (colon and wq) then press enter.

The colon means you are entering a command. The letters wq means write and quit. So using `:w` only means you intend to just `save` your comments but not quit yet. In some cases, it's enough to just quit (using `:q`) and git saves it automatically. To be safe though, use `:wq`.commit messages.

__Git Commit Best Practices__

- Your commits should sound like you are commanding something.
- If your commit message is long, use `git commit` only and write one sentence (still a command) as the header, separate by new line and add details.

Example 

```
Add file octocat.txt

This is my first commit with a long message. Eiusmod proident pariatur ex irure consequat do eiusmod incididunt esse eu ex nisi nostrud. Anim ut deserunt exercitation do consequat et aliqua aliquip. Cillum eiusmod duis nisi mollit esse dolore.
```

Try doing a `git status` again and git will tell you that there are no changes.

```
On branch master
nothing to commit, working directory clean
```

To view your changes, you can do a `git log` which should output something like:

```
commit 1660e70ae1934f3831b4b1f509e52500bf5aab3a
Author: Ervinnne Sodusta <ervinnesodusta13@yahoo.com.ph>
Date:   Fri Mar 15 23:58:00 2019 +0800

    Add file octocat.txt

    Test
```

## Pushing Your Updates Online

We may use `github` for this. Follow along the instructor as he shows you how to register for an account and create a repository.

### Adding a Remote Repository & Pushing

Use the command:

```bash
git remote add origin http://github.com/your-repository
```

(Replace http://github.com/your-repository with your own repository.)

After adding a repository, you can now upload your changes by doing a `push` with:

```bash
git push origin master
```

`master` is what we call a `Git` "`branch`". For now, just think of it as a literal branch from a `tree of changes`.

## Git Branches

To understand branches, we'll have to go back and review your commits.

The simplest explanation I can think of is that you may treat your commits as if they are linked lists of sorts that contain pointers to the previous commits.

![fig-ext 4 Commits](https://git-scm.com/figures/18333fig0302-tn.png)

A branch in Git is simply a lightweight movable pointer to one of these commits. The default branch name in Git is `master`. As you initially make commits, you’re given a master branch that points to the last commit you made. Every time you commit, it moves forward automatically.

![fig-ext 5 - Branch](https://git-scm.com/figures/18333fig0303-tn.png)

Of course, this means you can have multiple branches.

## What Git Branches Looks Like in the Real World

In your future workplace, you'll likely have the standard setup of `develop` -> `staging`/`qa` -> `master` branches.

Go ahead and create these 3 branches as an excercise:

```bash
git branch qa

git checkout qa
```

The `checkout` command will "move" your source code to the branch you specified. Each branch can have different set of commits.

Upload this branch by `pushing`:
```bash
git push origin qa
```

You can also create the branch and checkout at the same time with:

```bash
git checkout -b develop

git push origin develop
```

Check git and you should see that you now have 3 branches.

### The 3 Branches

__Develop branch__ is the branch where you and your teammates first should merge your work. This is where you update your team and get updates from them.

__Staging/QA branch__ is the branch where you push changes that are ready for testing.

__Master branch__ should only contain stable code. Meaning tested and working code. This is the branch that's normally promoted to production.

## Feature Branches and Merging

Your workflow when you're working with other people in a team is that whenever you work on a feature, you create a `feature branch`.

Feature branches are branches from the `develop` branch that are specific to a feature or fix you are working on.

After finishing your work on your branch, you then `merge` your branch on develop.

### Activity

Practice creating feature branches that you merge to the `develop` branch.

Let's work on a feature that adds a main and sub webpage to our repository.

#### Doing changes on a branch

```bash
git checkout -b feature-main-and-sub-webpages
```

Create some files a main webpage that will link to a sub page we'll do later.

File `index.html`
```bash
<html>
    <head>
        <title>Main Webpage | My Webpage</title>
    </head>
    <body>
        <h1>This is the main webpage</h2>
        <a href="/sub.html">Click to navigate to sub</a>
    </body>
</html>
```

Commit your change:

```bash
git status
git add .
git status
git commit -m "Add main webpage that links to a sub page"
git status
```

Tip: before and after a git command, do a `git status` to monitor what happens to the repository and develop a habit of it.

File `sub.html`

```bash
<html>
    <head>
        <title>Sub Webpage | My Webpage</title>
    </head>
    <body>
        <h1>This is the sub webpage</h2>    
    </body>
</html>
```

Commit your change:

```bash
git status
git add .
git status
git commit -m "Add subpage"
git status
```

After locally testing your updates, we're now ready to add our feature branch.

```bash
git status
git push origin feature-main-and-sub-webpages
```

#### Merging the feature branch to develop

Let's merge with the option `--no-ff` meaning no fast forward. Fast forwarding in git will result in loss of our commits as they are summarized in a merge. We usually want to keep the logs of our changes so let's use `--no-ff`.

```bash
git status
git checkout develop
git merge --no-ff feature-main-and-sub-webpages
git push origin develop
```

Note: when merging, git may ask you for comments. Git will likely open up a `Vim` editor. Do the usual, you can opt to not provide comments and just do `ESC` then type `:q` then press `Enter` or write a comment then do `:wq` instead of of `:q`.

That's it! Now your changes can be retrieved by your teammates by `pulling`:

```bash
git pull origin develop
```

#### Promoting Changes to Staging/QA Branch

```bash
git status
git checkout qa
git merge merge develop
git push origin qa
```

Notice that here, we don't have to do a `--no-ff`. We can just check for the commits in the develop. We don't have to do that in other branches.

#### On Your Own

Activity (Quick): Assume that the QA team is finished validating your work and gives a go signal to promote to production. The first thing you do is merge qa with master.

Normally you don't do this manually, but instead merge in the web (github in our case).

#### Pull Requests

Just a quick discussion, but in some cases, you don't have access to merge to develop. The team can have a policy for you to create `pull requests` instead.

With `pull requests`, your team can create a system where all changes to be merged to develop is reviewed by everyone first (or by pairs in `pair programming`).

With `pull requests`, you can also set to have your feature branches deleted automatically when your request is updated. (You'll have to manually do this otherwise.)

#### Cloning Repositories

To get an already existing repository, you simply `clone` the repository locally by:

```bash
git clone http://github.com/your-repository.git
```

Replace 'http://github.com/your-repository' with the actual repository link

### Test Yourselves

#### Activity:

Steps:
1. Find a partner (or a team of 3)
2. Have someone create a repository
3. Create the 3 main branches `develop`, and `qa` (`master` is already there by default)
4. The team members will then clone the repository and work on it.
5. Work on your own HTML files that display your names in it, it's up to you how to display in mark up. The rule is that you must create your own feature branches.
6. Once you're done, merge and push your changes to `develop`
7. Have someone in the team create an `index.html` file that has links to all your pages using the `<a>` tag. This will be pushed to develop as well, test your updates.
8. After locally testing, push your changes to the `qa` branch.
9. Once you're done testing, give the link to the repository to your neighbor group and vice versa.
10. Run locally the `qa` branch and test the other group's work. Once validated, give them an "OK" signal to push.
11. Once the other group gives an "OK", promote your changes to `master` branch.
