package gurl

import (
	"net/url"
	"path/filepath"
	"strings"
)

func GetQueryParam(u, param string) (string, error) {
	parsedUrl, err := url.Parse(u)
	if err != nil {
		return "", err
	}
	values := parsedUrl.Query()
	return values.Get(param), nil
}

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
	hashParams := strings.Split(parsedUrl.Fragment, "&")
	var newHashParams []string
	for _, p := range hashParams {
		pair := strings.Split(p, "=")
		if pair[0] == param {
			newHashParams = append(newHashParams, param+"="+value)
		} else {
			newHashParams = append(newHashParams, p)
		}
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
