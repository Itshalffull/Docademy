# Views Contextual filter filter

This module allows you to use the contextual filter value when filtering. So if you add a filter criteria, that form will get the contextual filter value options on the form.

To make this work, the module defines a class for every [default] Views filter that extends the parent. If the criteria filter value is being used, we hijack the query and do our own thing rather than the default functionality.
