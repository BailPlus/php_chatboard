URL = 'http://localhost/.test.php'

import httpx,sys,os

psw = os.urandom(16).hex()
print(psw)
php = sys.stdin.read()
resp = httpx.get(URL,params={'psw':psw,'php':php})
resp.raise_for_status()
print(resp.text)
