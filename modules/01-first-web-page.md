# Your first Web Page Published

Let's dive straight through creating a very simple web page with an overused Hello World :)

## Creating a Plain Project

Create a folder called `hello-world` anywhere you like or if you have laragon installed, create a folder in `/laragon/www/`

Change drive to that folder and create a file called `index.html` inside with the following contents.

File `index.html`
```html
<h1>Hello World</h1>
```

Try opening your file in google chrome and you should see `Hello World` in large text (header 1).

## Deploying to a Free Host

Let's use a popular free hosting service [000 Webhost](https://ph.000webhost.com/) to deploy our web page.

Note that the instructor __does NOT recommend using this for your production needs__, but we'll use it for now since it's free. More on this later.

Create an account in [000 Webhost's Sign Up Page](https://ph.000webhost.com/free-website-sign-up).

Tip: For now, use a simple password that you don't use anywhere else so that in case you have troubles setting up, you can let the instructor check your configurations (that includes the password) to help you. We'll use your password later when automating deployment.

### Create a new Website in 000 Webhost

It's kinda hard to find since it mixes with the other content but scroll down and find this:

![Fig1 Create new Site](/img/fig1.png)

Follow along the instructor as you make your first published website and uploading your files.

### Uploading Updates using Git FTP

Instead of manually uploading, you have an option to use FTP management software like filezilla. However, since `Git` will pretty much be integrated with your work everyday (we'll discuss more later), we'll use `git-ftp` instead.

Note: for continuous deployment, it's preferable we have something that can run on console like git ftp.

#### Installing & Setting up Git FTP

Open up your `git bash` and key in the commands:

```bash
curl https://raw.githubusercontent.com/git-ftp/git-ftp/master/git-ftp > /bin/git-ftp

chmod 755 /bin/git-ftp
```

Get your FTP Details in the `settings` page of your website, it should look something like below:

![Fig 2 - Website FTP Details](/img/fig2.png)

Then set up your application as a git repository and add the FTP details:

```bash

git init

git config git-ftp.url "<Host Name here>"
git config git-ftp.user "<Your username here>"
git config git-ftp.password "<Your password here>"

```

To upload all files:

```bash
git-ftp init
```

When updating the server:

```bash
git-ftp upload
```
