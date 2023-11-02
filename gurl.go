package gurl

import (
	"net/url"
	"path/filepath"
	"strings"
)

// GetQueryParam retrieves the value of a specified query parameter from a URL.
//
// Parameters:
//
//	url: The URL from which to retrieve the query parameter.
//	param: The name of the query parameter to retrieve.
//
// Returns:
//
//	A string containing the value of the query parameter, and an error if any occurred.
//
// Example:
//
//	result, err := GetQueryParam("http://example.com/?p1=1&p2=2", "p1")
//	if err != nil {
//	  panic(err)
//	}
//	fmt.Println(result) // Output: "1"
func GetQueryParam(u, param string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	values := parsedUrl.Query()
	return values.Get(param), nil
}

// SetQueryParam sets the value of a specified query parameter in a URL and returns the new URL.
//
// Parameters:
//
//	url: The URL in which to set the query parameter.
//	param: The name of the query parameter to set.
//	value: The value to set the query parameter to.
//
// Returns:
//
//	A string containing the new URL, and an error if any occurred.
//
// Example:
//
//	result, err := SetQueryParam("http://example.com/?p1=1&p2=2", "p1", "3")
//	if err != nil {
//	  panic(err)
//	}
//	fmt.Println(result) // Output: "http://example.com/?p1=3&p2=2"
func SetQueryParam(u, param, value string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	values := parsedUrl.Query()
	values.Set(param, value)
	parsedUrl.RawQuery = values.Encode()
	return parsedUrl.String(), nil
}

func DelQueryParam(u, param string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	values := parsedUrl.Query()
	values.Del(param)
	parsedUrl.RawQuery = values.Encode()
	return parsedUrl.String(), nil
}

func GetHashParam(u, param string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	hashParams := strings.Split(parsedUrl.Fragment, "&")
	for _, p := range hashParams {
		pair := strings.Split(p, "=")
		if pair[0] == param {
			return pair[1], nil
		}
	}
	return "", nil
}

func SetHashParam(u, param, value string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	fra := parsedUrl.Fragment
	hashParams := []string{}
	if fra != "" {
		hashParams = strings.Split(parsedUrl.Fragment, "&")
	}
	var newHashParams []string
	paramExists := false
	for _, p := range hashParams {
		pair := strings.Split(p, "=")
		if pair[0] == param {
			newHashParams = append(newHashParams, param+"="+value)
			paramExists = true
		} else {
			newHashParams = append(newHashParams, p)
		}
	}
	if !paramExists {
		newHashParams = append(newHashParams, param+"="+value)
	}
	parsedUrl.Fragment = strings.Join(newHashParams, "&")
	return parsedUrl.String(), nil
}

func DelHashParam(u, param string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	hashParams := strings.Split(parsedUrl.Fragment, "&")
	var newHashParams []string
	for _, p := range hashParams {
		pair := strings.Split(p, "=")
		if pair[0] != param {
			newHashParams = append(newHashParams, p)
		}
	}
	parsedUrl.Fragment = strings.Join(newHashParams, "&")
	return parsedUrl.String(), nil
}

func GetPath(u string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	return parsedUrl.Path, nil
}

func SetPath(u, newPath string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	parsedUrl.Path = newPath
	return parsedUrl.String(), nil
}

func GetHost(u string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	return parsedUrl.Host, nil
}

func SetHost(u, newHost string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	parsedUrl.Host = newHost
	return parsedUrl.String(), nil
}

func GetHostname(u string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	hostParts := strings.Split(parsedUrl.Host, ":")
	return hostParts[0], nil
}

func SetHostname(u, newHostname string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	hostParts := strings.Split(parsedUrl.Host, ":")
	if len(hostParts) > 1 {
		parsedUrl.Host = newHostname + ":" + hostParts[1]
	} else {
		parsedUrl.Host = newHostname
	}
	return parsedUrl.String(), nil
}

func GetProtocol(u string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	return strings.Split(parsedUrl.Scheme, ":")[0], nil
}

func SetProtocol(u, newProtocol string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	parsedUrl.Scheme = newProtocol
	return parsedUrl.String(), nil
}

func CheckValid(u string) bool {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return false
	}
	return parsedUrl.Scheme != "" && parsedUrl.Host != ""
}

func CheckValidHttpUrl(u string) bool {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return false
	}
	return parsedUrl.Scheme == "http" || parsedUrl.Scheme == "https"
}

func GetUrlFileType(u string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	return strings.TrimPrefix(filepath.Ext(parsedUrl.Path), "."), nil
}
