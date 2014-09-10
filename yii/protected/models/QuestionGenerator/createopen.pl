#!/usr/bin/perl -wi

$type = 'Open Answer';
$category = $ARGV[1];
$status = 'Approved';
$user_id = 1;
open(I,"<$ARGV[0]");
while(<I>) {
  chomp;
  $line = $_;
  $line =~ s/^\s+//g;
  $line =~ s/\s+$//g;
  $line =~ s/\#.*//g;
  $line =~ s/\(\s*\+\s*\.*\)//g;
  print "insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select $user_id, \"$line\",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b,question_type c where a.name='$category' and b.name='$status' and c.name='$type';\n";
}
