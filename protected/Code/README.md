# Application custom code
This directory is used as the root for custom application php code.
The logical namespace related to this directory is `App.Code`

## Namespace
You should'nt use any namespace in this directory.
Classes should be in the PHP root namespace as all PRADO classes. But you can organise your files using subdirectories.
 
## Importing classes
Classes are used as PRADO ones using `Prado::using('logical_name_space')` or the helper function `using('logical_name_space')`
Example: 
```Php
using('App.Code.Exception.NAppException');
```