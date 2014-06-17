#ifndef LIBRARY_DJS_ANALYSIS_TOKENS_TOKEN_H
#define LIBRARY_DJS_ANALYSIS_TOKENS_TOKEN_H

#include <iostream>
#include <string>
#include "tokenType.h"

namespace library
{
	namespace djs
	{
		namespace analysis
		{
			namespace tokens
			{
				class Token
				{
					public:
						Token();
						~Token();
						std::string getValue() const;
						int getLine() const;
						tokenType getType() const;

					protected:
						int line;
						tokenType type;
						std::string value;
				};
			}
		}
	}
}

#endif // LIBRARY_DJS_ANALYSIS_TOKENS_TOKEN_H
