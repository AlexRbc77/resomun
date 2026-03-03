from sys import argv

clause = argv[1]
clause_type = argv[2]
preambs = open("preambs.txt", "r").read().splitlines()
operatives = open("operatives.txt", "r").read().splitlines()

def get_word_from_clause(clause, clause_type):
    word_list = operatives if clause_type == 'o' else preambs
    for i in word_list:
        if i.strip() in clause:
            return i.strip()
    return "" 


def format_word(word, clause_type):
    if clause_type == 'o':
        return '<b>' + word + '</b>'
    else:
        return '<u>' + word + '</u>'
    
word = get_word_from_clause(clause, clause_type)

new_clause = clause.replace(word, format_word(word, clause_type), 1)
print(new_clause)

