#!/usr/bin/python
import sys
import ujson

def ParseLine(line):
	""" break a phase into chord and lyric """

	phrase=[]
	madis = [ x.strip() for x in line.split("|")]
	for madiTxt in madis:
		if madiTxt == "":
			continue

		madi=[]
		pieces = [ x.strip() for x in madiTxt.split("[")]
		for piece in pieces:
			if( piece == ""):
				continue
			idx = piece.find("]")
			if idx < 0:
				madi.append( {"chord":"", "lyric": piece.strip()})
			else:
				madi.append( {"chord":piece[0:idx].strip(), "lyric": piece[idx+1:].strip()})
		phrase.append(madi)
	return phrase

bColumn = False
if len(sys.argv) > 1 and sys.argv[1] == "column":
	bColumn = True

### Read Header
metaInfo = ujson.loads(sys.stdin.readline())
#print ujson.dumps(metaInfo, ensure_ascii=False)

### Read Content
data = []
maxMadi = 0
CMT_HEADER = "{comment:"
for line in sys.stdin:
	line = line.strip()
	if line == "" or line[0]=="#":
		continue

	if line[0] == '{' :
		if line == "{column}":
			data.append({"type":"column"})
		if line.startswith(CMT_HEADER):
			data.append({"type":"comment", "comment":line[len(CMT_HEADER):-1].strip()})
		continue
	phrase = ParseLine(line)
	maxMadi = max( maxMadi, len(phrase) )
	data.append(phrase)

### PRINT
print("<table id=dadan class=dadan><tr><td class=choga>")
print("<h1>%s</h1>"% metaInfo["title"].encode('utf-8'))
if metaInfo["subtitle"] != "":
	print("<h2>- %s -</h2>"% metaInfo["subtitle"].encode('utf-8'))
print("<table border=0 width=100%><tr><td class=key>\n");
print("<table><tr>\n");
if metaInfo["original"] != "":
	print("<td> Origianl: </td><td>%s</td>\n"% metaInfo["original"])
if metaInfo["key"] != "":
	print("<td>Key: </td><td class=chord>%s</td>\n" % metaInfo["key"])
print("</tr></table>\n");
print("</td><td class=musician>%s</td>"% metaInfo["musician"].encode('utf-8'))
print("</tr></table>\n<hr>\n");

width = 100 / maxMadi
for phrase in data:
	if isinstance(phrase, dict): 
		phrase_type = phrase["type"]
		if phrase_type == "comment":
			print(phrase["comment"]+"<br>")
			continue
		if phrase_type == "column":
			if bColumn:
				print("</td><td class=choga>")	# multi-column
			else:
				print("</td></tr><tr><td class=choga>")	# 1-column
			continue

	nMadi = len(phrase);
	print("<table class=phrase width=%d%%><tr>"%(width * nMadi))
	for madi in phrase:
		print("<td width=%d%%><table class=madi><tr>"%(100/nMadi))
		for piece in madi:
			#print("	<td class=chord>%s</td>"% piece["chord"],end="") #for python3
			print "	<td class=chord>%s &nbsp;</td>"% piece["chord"],
		print("</tr><tr>")
		for piece in madi:
			#print("	<td> %s</td>"% piece["lyric"],end="")
			if piece["chord"] == "" :
				#print("	<td align=right class=lyric>&nbsp;%s</td>"% piece["lyric"],end="")
				print "	<td align=right class=lyric>&nbsp;%s</td>"% piece["lyric"],
			else:
				#print("	<td class=lyric>%s&nbsp;</td>"% piece["lyric"],end="")
				print "	<td class=lyric>%s&nbsp;</td>"% piece["lyric"],
		print("\n</tr></table></td>") #madi
	print("</tr></table>") #phrase
print("</td></tr></table>") #dadan

