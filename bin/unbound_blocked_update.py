import os

input_file_path = "../domains.txt"
output_file_path = "../unbound.conf.blocked";
server_line = "server:"

try:
    fin = open(input_file_path, "r")
except OSError:
    sys.exit()

with fin:
    list = fin.read()
    dns_records = [server_line]
    for line in list.splitlines():
        domain = line.strip()
        if domain:
            # Удаление символа ";" в конце доменного имени
            domain = domain.rstrip(";")
            dns_record = f'local-data: "{domain} A 127.0.0.1"'
            dns_records.append(dns_record)

    with open(output_file_path, "w") as fout:
        fout.write('\n'.join(dns_records))
    fin.close()
    fout.close()
