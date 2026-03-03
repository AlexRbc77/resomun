import smtplib

TO = 'alexandrerobic312@gmail.com'
SUBJECT = 'Test Email'
TEXT = 'Automatic message from ResoMUN'

# Gmail Sign In
gmail_sender = 'resomun@gmail.com'
gmail_passwd = 'ElmoSecGen2020'

server = smtplib.SMTP('smtp.gmail.com', 587)
server.ehlo()
server.starttls()
server.login(gmail_sender, gmail_passwd)

BODY = '\r\n'.join(['To: %s' % TO,
                    'From: %s' % gmail_sender,
                    'Subject: %s' % SUBJECT,
                    '', TEXT])

try:
    server.sendmail(gmail_sender, [TO], BODY)
    print ('email sent')
except:
    print ('error sending mail')

server.quit()