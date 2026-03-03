from sys import argv 
from os import system

inp = argv[1]
output = argv[2]

system(f"sudo google-chrome --no-sandbox --headless --disable-gpu --no-margins --print-to-pdf-no-header --print-to-pdf={output} {inp}")
system(f"chmod 777 {output}")

print(f"Printed {inp} to pdf")

