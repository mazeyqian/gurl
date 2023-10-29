package gurl

import (
	"testing"
)

func TestGetQueryParam(t *testing.T) {
	result, err := GetQueryParam("http://example.com/?t1=1&t2=2", "t1")
	if err != nil || result != "1" {
		t.Errorf("GetQueryParam was incorrect, got: %s, want: %s.", result, "1")
	}
}

func TestSetQueryParam(t *testing.T) {
	result, err := SetQueryParam("http://example.com/?t1=1&t2=2", "t1", "3")
	if err != nil || result != "http://example.com/?t1=3&t2=2" {
		t.Errorf("SetQueryParam was incorrect, got: %s, want: %s.", result, "http://example.com/?t1=3&t2=2")
	}
}

func TestDelQueryParam(t *testing.T) {
	result, err := DelQueryParam("http://example.com/?t1=1&t2=2", "t1")
	if err != nil || result != "http://example.com/?t2=2" {
		t.Errorf("DelQueryParam was incorrect, got: %s, want: %s.", result, "http://example.com/?t2=2")
	}
}

func TestGetPath(t *testing.T) {
	result, err := GetPath("http://example.com/path/to/resource")
	if err != nil || result != "/path/to/resource" {
		t.Errorf("GetPath was incorrect, got: %s, want: %s.", result, "/path/to/resource")
	}
}

func TestSetPath(t *testing.T) {
	result, err := SetPath("http://example.com/path/to/resource", "/new/path")
	if err != nil || result != "http://example.com/new/path" {
		t.Errorf("SetPath was incorrect, got: %s, want: %s.", result, "http://example.com/new/path")
	}
}

func TestGetHost(t *testing.T) {
	result, err := GetHost("http://example.com/path/to/resource")
	if err != nil || result != "example.com" {
		t.Errorf("GetHost was incorrect, got: %s, want: %s.", result, "example.com")
	}
}

func TestSetHost(t *testing.T) {
	result, err := SetHost("http://example.com/path/to/resource", "newhost.com")
	if err != nil || result != "http://newhost.com/path/to/resource" {
		t.Errorf("SetHost was incorrect, got: %s, want: %s.", result, "http://newhost.com/path/to/resource")
	}
}

func TestGetHostname(t *testing.T) {
	result, err := GetHostname("http://subdomain.example.com/path/to/resource")
	if err != nil || result != "subdomain.example.com" {
		t.Errorf("GetHostname was incorrect, got: %s, want: %s.", result, "subdomain")
	}
}

func TestSetHostname(t *testing.T) {
	result, err := SetHostname("http://subdomain.example.com/path/to/resource", "newsubdomain.example.com")
	if err != nil || result != "http://newsubdomain.example.com/path/to/resource" {
		t.Errorf("SetHostname was incorrect, got: %s, want: %s.", result, "http://newsubdomain.example.com/path/to/resource")
	}
}

func TestGetProtocol(t *testing.T) {
	result, err := GetProtocol("http://example.com/path/to/resource")
	if err != nil || result != "http" {
		t.Errorf("GetProtocol was incorrect, got: %s, want: %s.", result, "http")
	}
}

func TestSetProtocol(t *testing.T) {
	result, err := SetProtocol("http://example.com/path/to/resource", "https")
	if err != nil || result != "https://example.com/path/to/resource" {
		t.Errorf("SetProtocol was incorrect, got: %s, want: %s.", result, "https://example.com/path/to/resource")
	}
}

func TestCheckValid(t *testing.T) {
	if !CheckValid("http://example.com/?#2333?t1=1&t2=2&t3=3&t4=4") {
		t.Errorf("CheckValid was incorrect, got: false, want: true.")
	}
}

func TestCheckValidHttpUrl(t *testing.T) {
	if !CheckValidHttpUrl("http://example.com/?#2333?t1=1&t2=2&t3=3&t4=4") {
		t.Errorf("CheckValidHttpUrl was incorrect, got: false, want: true.")
	}
}

func TestGetUrlFileType(t *testing.T) {
	result, err := GetUrlFileType("https://example.com/a/b/c.png")
	if err != nil || result != "png" {
		t.Errorf("GetUrlFileType was incorrect, got: %s, want: %s.", result, "png")
	}
}