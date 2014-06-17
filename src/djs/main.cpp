#include <iostream>
#include "library/djs/DJS.h"
#include "library/djs/analysis/automata/State.h"

using namespace std;

void machin(library::djs::analysis::automata::State& s)
{

	library::djs::analysis::automata::State s2;
	s.addTransition(s2);
}

int main(int argc, char** argv)
{
//	library::djs::DJS djs;
//
//	library::djs::analysis::automata::State s;
//	machin(s);

	if(argc > 1)
	{
    	cout << "arg 1: " <<argv[0] << endl;
    	cout << "arg 2: " << argv[1] << endl;
	}


    return 0;
}
