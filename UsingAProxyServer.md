#This page describes how to enable the proxy usage for the generator

# Instructions #

If you are behind a firewall or have to use a proxy server for any reason, you have to set the environment variable HTTP\_PROXY. The framework will use the given proxy servser for curl requests.

If you have to specify a port, use following format:

```
servername:port
```