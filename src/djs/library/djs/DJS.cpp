#include "DJS.h"

namespace library
{
	namespace djs
	{
		DJS::DJS()
		{
			//ctor
		}

		DJS::~DJS()
		{
			//dtor
		}

		std::string DJS::parseFile(std::string filename, std::string fileout)
		{
			library::djs::analysis::Parser parser(this);
			std::string content = parser.parseFile(filename);

			if(fileout != "")
			{
				return content;
			}
			else
			{
				return content;
			}
		}
	}
}
