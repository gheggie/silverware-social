# SilverWare Social Module

Provides a sharing component for use with [SilverWare][silverware], allowing pages to be shared via
a series of sharing buttons for social media services.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Issues](#issues)
- [Contribution](#contribution)
- [Maintainers](#maintainers)
- [License](#license)

## Requirements

- [SilverWare][silverware]

## Installation

Installation is via [Composer][composer]:

```
$ composer require silverware/social
```

## Configuration

As with all SilverStripe modules, configuration is via YAML. Page settings for sharing are applied
using the included `PageExtension` class, allowing sharing to be disabled for certain pages.

## Usage

This module provides a `SharingComponent` which can be added to a [SilverWare][silverware] template
or layout using the CMS. A `SharingComponent` renders a series of social media sharing buttons for
the current page, allowing the user to easily share the page with others.

Included with the repository is `SharingButton`, which forms a base class from which to extend sharing button
implementations, and `EmailSharingButton` which renders a button for sharing the current page via
email.

Buttons can be added and removed using the Buttons tab in the `SharingComponent` CMS interface.

### Disabling Sharing

After installation, this module adds a "Sharing" section to the Settings tab for pages, with a
"Sharing disabled" checkbox. Simply check this box for a particular page, and the sharing component
will not be shown.

## Issues

Please use the [GitHub issue tracker][issues] for bug reports and feature requests.

## Contribution

Your contributions are gladly welcomed to help make this project better.
Please see [contributing](CONTRIBUTING.md) for more information.

## Maintainers

[![Colin Tucker](https://avatars3.githubusercontent.com/u/1853705?s=144)](https://github.com/colintucker) | [![Praxis Interactive](https://avatars2.githubusercontent.com/u/1782612?s=144)](http://www.praxis.net.au)
---|---
[Colin Tucker](https://github.com/colintucker) | [Praxis Interactive](http://www.praxis.net.au)

## License

[BSD-3-Clause](LICENSE.md) &copy; Praxis Interactive

[composer]: https://getcomposer.org
[silverware]: https://github.com/praxisnetau/silverware
[issues]: https://github.com/praxisnetau/silverware-social/issues
