import psycopg2
import sys

try:       
    cnx = psycopg2.connect(dbname='resomun', host='localhost', user='postgres', password='Pikachu1234')
except:
    sys.exit(1)
    
cursor = cnx.cursor()
cnx.autocommit = True

monsters = """Elmo Monster
Big Bird
Bert Sesame
Grover Monster
Count VonCount
Oscar TheGrouch
Kermit TheFrog
Miss Piggy""".split('\n')

print(monsters)