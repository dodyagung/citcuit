# Contributing Guide

## Create an Issue

Found a bug? Have a question? Suggest an enhancement? **Create an issue** is a great way to discuss it.

This GitHub [issues guide](https://guides.github.com/features/issues) might be useful for you.

## Create a Fork & Pull Request

This method is suitable for **developer**. If you are able to patch a bug yourself add a feature, just follow this steps :

1. Fork this CitCuit repository
2. Clone the fork to your computer locally and run `composer install`
3. Create a new branch (ex: `fix-typo`) from :
    - `master` branch for bugs
    - `develop` branch for new features
4. Copy `.env.example` to `.env` and edit the file
5. Make changes, commit and push it to your fork
6. Create a pull request back to CitCuit repository from your new branch to :
    - `master` branch for bugs
    - `develop` branch for new features

This GitHub [fork guide](https://guides.github.com/activities/forking) and [pull request guide](https://help.github.com/articles/using-pull-requests) might be useful for you.

## Which Branch?

-   `master` branch for stable version, active support, bug-fix, and used on [citcuit.in](https://citcuit.in)
-   `develop` branch for developments, enhancements, and new features. This will be merged into master branch then.
