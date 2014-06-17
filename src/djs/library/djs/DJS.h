#ifndef LIBRARY_DJS_DJS_H
#define LIBRARY_DJS_DJS_H

#include<iostream>
#include<string>
#include "analysis/Parser.h"

namespace library
{
	namespace djs
	{
		class DJS
		{
			public:
				DJS();
				virtual ~DJS();
				std::string parseFile(std::string filename, std::string fileout = "");

			protected:

		};
	}
}

#endif // LIBRARY_DJS_DJS_H
