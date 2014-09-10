#!/usr/bin/perl -wi

use Data::Dumper;

$type = 'Multiple Choice';
$category = $ARGV[1];
$status = 'Approved';
$user_id = 1;


$questions = {};
$c = 0;
open(I,"<$ARGV[0]");
while(<I>) {
  chomp;
  $line = $_;
  $line =~ s/(\()?\#.*(\()?//g;
  $line =~ s/^\s+//g;
  $line =~ s/\s+$//g;
# print $line."\n";
  if (!($line =~ m/^\w+\./)) {
    $current = $line;
    $questions->{$current} = [];
  } else {
    $mc = $line;
    $mc =~ s/^\w+\.//ig;
    $mc =~ s/^\s+//g;
    $mc =~ s/\s+$//g;
    push(@{$questions->{$current}},$mc);
  }
}

foreach $q(keys %$questions) {
  print "insert into question(user_id,content,question_category_id,question_status_id,question_type_id) select $user_id, \"$q\",a.question_category_id,b.question_status_id,c.question_type_id from question_category a, question_status b, question_type c where a.name='$category' and b.name='$status' and c.name='$type';\n";
  print "set \@q_id = (select LAST_INSERT_ID() from question limit 1);\n";
  $index = 1;
  foreach $choice(@{$questions->{$q}}) {
    print "insert into question_choice(question_id,content,choice_order) values(\@q_id,\"$choice\",$index);\n";
#select LAST_INSERT_ID(),'$choice',$index from question;\n";
    $index++;
  }
}
