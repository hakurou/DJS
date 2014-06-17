#ifndef LIBRARY_DJS_ANALYSIS_PARSER_H
#define LIBRARY_DJS_ANALYSIS_PARSER_H

#include<iostream>
#include<string>

namespace library
{
	namespace djs
	{
		class DJS;

		namespace analysis
		{
			class Parser
			{
				public:
					Parser(library::djs::DJS* djs);
					virtual ~Parser();
					std::string parseFile(std::string filename);

				protected:
					library::djs::DJS* djs;
			};
		}
	}
}

#endif // LIBRARY_DJS_ANALYSIS_PARSER_H
