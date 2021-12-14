import requests
import socket
IP_URL = "http://YOURDOMAIN/api/ip/"
PI_ID = 1


# Update IP address when powered on
s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
s.connect(("8.8.8.8", 80))
my_ip = s.getsockname()[0]
s.close()


payload = {"source_pi_id": PI_ID, "ip": my_ip}
request = requests.post(IP_URL, json=payload)