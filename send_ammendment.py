from sys import argv
import email, smtplib, ssl

from email import encoders
from email.mime.base import MIMEBase
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

ammendment_pdf = argv[1]
ammendment_contents = argv[2]
chair_emails = argv[3:]


if ammendment_pdf.split('.')[1] != "pdf":
    print("Wrong pdf file")
    exit(0)


print(f"Sending {ammendment_pdf} to chairs {chair_emails}")

# Gmail Sign In
gmail_sender = 'resomun@gmail.com'
gmail_passwd = 'ElmoSecGen2020'

def send_email(contents, subject, receiver, pdf_file):
    # Create a multipart message and set headers
    message = MIMEMultipart()
    message["From"] = gmail_sender
    message["To"] = receiver
    message["Subject"] = subject

    # Add body to email
    message.attach(MIMEText(contents, "plain"))

    filename = pdf_file  # In same directory as script

    # Open PDF file in binary mode
    with open(filename, "rb") as attachment:
        # Add file as application/octet-stream
        # Email client can usually download this automatically as attachment
        part = MIMEBase("application", "octet-stream")
        part.set_payload(attachment.read())

    # Encode file in ASCII characters to send by email    
    encoders.encode_base64(part)

    # Add header as key/value pair to attachment part
    part.add_header(
        "Content-Disposition",
        f"attachment; filename= {filename}",
    )

    # Add attachment to message and convert message to string
    message.attach(part)
    text = message.as_string()

    # Log in to server using secure context and send email
    context = ssl.create_default_context()
    with smtplib.SMTP("smtp.gmail.com", 465) as server:
        server.starttls(context=context)
        server.login(gmail_sender, gmail_passwd)
        server.sendmail(gmail_sender, receiver, text)

for person in chair_emails:
    parts = ammendment_contents.split(';')
    if parts[0] == 'ADD' or parts[0] == 'EDIT':
        ammend_type = parts[0]
    else:
        ammend_type = "DELETE"

    clause_number = parts[1]
    clause = parts[2]
    contents = f"An ammendment has been submitted in your committee. The delegate wishes to {ammend_type} a clause, subclause or subsubclause."
    contents += f"The text concerned reads as follows:\t{clause_number}{clause}"
    contents += "Consult the attached document to see more details"
    send_email(contents, "Ammendment submission", person, ammendment_pdf)