import re

with open('input.txt', 'r') as file:
	content = file.read()
	pattern = r'mul\(([0-9]{1,3}),([0-9]{1,3})\)'
	matches = re.findall(pattern, content)
	total = 0
	for match in matches:
		total += int(match[0]) * int(match[1])
	print(total)
		