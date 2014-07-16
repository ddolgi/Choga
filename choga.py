#!/usr/bin/python
import sys

def MetaFormat(metaInfo, field, value):
	lower_field = field.lower()
	if lower_field in ["title", "t"]:
		metaInfo["title"]="<h1>%s</h1>"% value
	if lower_field in ["subtitle", "su"]:
		metaInfo["subtitle"]="<h2>- %s -</h2>"% value
	if lower_field in ["singer", "musician"]:
		metaInfo["musician"]="<td class=musician>%s</td>"% value
	if lower_field in ["key", "k"]:
		metaInfo["key"]=value
	if lower_field in ["capo"]:
		metaInfo["capo"]="Capo:%s "% value

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

def PrintMeta(metaInfo, field):
	if (field in metaInfo):
		print(metaInfo[field])

def main():
	### READ
	metaInfo = {}
	data = []
	maxMadi = 0
	for line in sys.stdin:
		line = line.strip()
		if line == "" or line[0]=="#":
			continue

		if line[0] == '{' :
			tokens = line[1:-1].split(':')
			field = tokens[0].lower().strip()
			if field == "column":
				data.append({"type":"column"})
			if len(tokens) >1: 
				value = tokens[1].strip()
				if field in ["comment", "c"]:
					data.append({"type":"comment", "comment":value})
				else:
					MetaFormat(metaInfo, field, value)
			continue
		phrase = ParseLine(line)
		maxMadi = max( maxMadi, len(phrase) )
		data.append(phrase)

	### PRINT
	print("<table id=dadan class=dadan><tr><td class=choga>")
	PrintMeta(metaInfo, "title")
	PrintMeta(metaInfo, "subtitle")
	print("<table border=0 width=100%><tr><td class=key><table><tr>");
	print("<td>");
	PrintMeta(metaInfo, "capo")
	print("</td><td>Key: </td><td class=chord>"),
	PrintMeta(metaInfo, "key")
	print("</td></tr></table>");
	print("</td>");
	PrintMeta(metaInfo, "musician")
	print("</tr></table>\n<hr>\n");

	width = 100 / maxMadi
	for phrase in data:
		if isinstance(phrase, dict): 
			phrase_type = phrase["type"]
			if phrase_type == "comment":
				print(phrase["comment"]+"<br>")
				continue
			if phrase_type == "column":
				#print("</td></tr><tr><td class=choga>")	# 1-column
				print("</td><td class=choga>")	# multi-column
				continue

		nMadi = len(phrase);
		print("<table class=phrase width=%d%%><tr>"%(width * nMadi))
		for madi in phrase:
			print("<td width=%d%%><table class=madi><tr>"%(100/nMadi))
			for piece in madi:
				#print("	<td class=chord>%s</td>"% piece["chord"],end="")
				print "	<td class=chord>%s &nbsp;</td>"% piece["chord"],
			print("</tr><tr>")
			for piece in madi:
				#print("	<td> %s</td>"% piece["lyric"],end="")
				if piece["chord"] == "" :
					print "	<td align=right> %s &nbsp;</td>"% piece["lyric"],
				else:
					print "	<td> %s &nbsp;</td>"% piece["lyric"],
			print("\n</tr></table></td>") #madi
		print("</tr></table>") #phrase
	print("</td></tr></table>") #dadan

main()
