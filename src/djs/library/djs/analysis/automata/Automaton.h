#ifndef LIBRARY_DJS_ANALYSIS_AUTOMATA_AUTOMATON_H
#define LIBRARY_DJS_ANALYSIS_AUTOMATA_AUTOMATON_H

#include <iostream>
#include <string>
#include "State.h"

namespace library
{
	namespace djs
	{
		namespace analysis
		{
			namespace automata
			{
				class Automaton
				{
					public:
						enum class status
						{
							S_ERROR,
							S_SUCCESS,
							S_SUCCESS_BACK,
							S_SUCCESS_IGNORE,
							S_IN_PROCESSING
						};

						Automaton();
						~Automaton();
						Automaton::status test(char letter);
						void getToken() const;
						void cleanBuffer();

					protected:
						State startState;
						State* currentState;
						std::string buffer;
						library::djs::analysis::tokens::tokenType tokenType;
				};
			}
		}
	}
}

#endif // LIBRARY_DJS_ANALYSIS_AUTOMATA_AUTOMATON_H
