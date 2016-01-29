#!/usr/bin/perl -wi

open(I,"<fix_quotes.txt");

while(<I>) {
  chomp;
  if (m/^(\d+)\s+(.*)/) {
    my $id = $1;
    my $quote = $2;
    print "update question set content=\"".$quote."\" where question_id=$id;\n";
  }
}

close(I);
