# Contributing Guide

## Create an Issue

Found a bug? Have a question? Suggest an enhancement? **Create an issue** is a great way to discuss it.

This GitHub [issues guide](https://guides.github.com/features/issues) might be useful for you.

## Create a Fork & Pull Request

This method is suitable for **developer**. If you are able to add a feature or patch the bug yourself, just follow this steps :

1. Fork CitCuit repo to your GitHub account
2. Clone it to your computer locally and checkout :
   * `master` branch for bug patch
   * `develop` branch for new feature or enhancement
3. Run `composer update`
4. Rename `.env.example` to `.env` and edit it according your config needs
5. Make changes, commit and push it to your GitHub
6. Create a pull request back to CitCuit repo and to :
   * `master` branch for your bug patch
   * `develop` branch for your new feature or enhancement

This GitHub [fork guide](https://guides.github.com/activities/forking) and [pull request guide](https://help.github.com/articles/using-pull-requests) might be useful for you.

## Which Branch?

- `master` branch is contain last stable version and can be used for production
- `develop` branch is used for development until it merged into master branch