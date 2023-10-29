# GURL: Go URL Manipulation Library

GURL is a Go library for URL manipulation and validation. It provides functions for getting and setting URL parameters, paths, hosts, and protocols, as well as checking URL validity and getting file types from URLs.

## Installation

To install GURL, execute the following command:

```bash
go get github.com/mazeyqian/gurl
```

## Usage

Here's a quick example of how to use GURL:

```go
package main

import (
	"fmt"
	"github.com/mazeyqian/gurl"
)

func main() {
	result, err := gurl.GetQueryParam("http://example.com/?t1=1&t2=2", "t1")
	if err != nil {
		panic(err)
	}
	fmt.Println(result) // Output: "1"
}
```

## Functions

| Function | Parameters | Return Value | Description |
|----------|------------|--------------|-------------|
| `GetQueryParam` | `url, param string` | `(string, error)` | Gets the value of a query parameter from a URL |
| `SetQueryParam` | `url, param, value string` | `(string, error)` | Sets the value of a query parameter in a URL |
| `DelQueryParam` | `url, param string` | `(string, error)` | Deletes a query parameter from a URL |
| `GetPath` | `url string` | `(string, error)` | Gets the path from a URL |
| `SetPath` | `url, newPath string` | `(string, error)` | Sets the path in a URL |
| `GetHost` | `url string` | `(string, error)` | Gets the host from a URL |
| `SetHost` | `url, newHost string` | `(string, error)` | Sets the host in a URL |
| `GetHostname` | `url string` | `(string, error)` | Gets the hostname from a URL |
| `SetHostname` | `url, newHostname string` | `(string, error)` | Sets the hostname in a URL |
| `GetProtocol` | `url string` | `(string, error)` | Gets the protocol from a URL |
| `SetProtocol` | `url, newProtocol string` | `(string, error)` | Sets the protocol in a URL |
| `CheckValid` | `url string` | `bool` | Checks if a URL is valid |
| `CheckValidHttpUrl` | `url string` | `bool` | Checks if a URL is valid and uses either the HTTP or HTTPS scheme |
| `GetUrlFileType` | `url string` | `(string, error)` | Gets the file type of a URL |

## Contributing

Contributions to GURL are welcome! Please submit a pull request or open an issue on our [GitHub repository](https://github.com/mazeyqian/gurl).

## License

GURL is licensed under the MIT License. For more details, please refer to the LICENSE file in this repository.
