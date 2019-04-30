# Design Experiments Plugin

A simple plugin to prototype design ideas in WP-Admin. **This plugin is not intended for use in production.** This repository is intended to be a quick way for designers to try out ideas and CSS updates in WP-Admin. Quick, messy code is encouraged to get ideas across!

The `master` branch is just an empty boilerplate: it sets up a plugin that enqueues a new, empty admin stylesheet to get you started. 

## To try an experiement: 

1. Switch to [a branch](https://github.com/wordpress/design-experiments/branches) that has an experiment.
2. Download a zip of the repository.
3. Upload to your test site as a plugin.
4. Activate the "Design Experiments" Plugin.

## To build your own experiment:

1. First, [fork the repository](https://help.github.com/en/articles/fork-a-repo). 
2. [Clone](https://help.github.com/en/articles/cloning-a-repository) your fork. If possible, place your local copy in the Plugins folder of your local dev site. Then you'll be able to activate the plugin directly from your WP Admin dashboard (If this isn't possible, follow the instructions above to install the plugin manually after you've edited it).
3. For simple CSS updates, you can either edit style.css directly, or edit `sass/style.scss` and compile using the method described below. 
4. Once you're ready to share your experiment, [open a PR](https://help.github.com/en/articles/creating-a-pull-request) and tell everyone about it. 

### To compile CSS:

1. Run `npm install` to install dependencies (You'll only have to do this once).
2. Run `npm run build` to compile the CSS once, or `npm run watch` to have it compile changes whenever you modify a sass file. 

## Questions or Improvements?

If you'd like to propose improvements to this plugin, feel free to open an [issue](https://github.com/WordPress/design-experiments/issues) or PR. Also feel free to ask in the [#design channel on WordPress.org Slack](http://wordpress.slack.com/messages/design/). 
