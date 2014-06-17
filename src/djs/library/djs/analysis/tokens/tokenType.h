
#ifndef LIBRARY_DJS_ANALYSIS_TOKENS_TOKENTYPE_H
#define LIBRARY_DJS_ANALYSIS_TOKENS_TOKENTYPE_H

namespace library
{
	namespace djs
	{
		namespace analysis
		{
			namespace tokens
			{
				enum class tokenType
				{
					TT_NONE,
					TT_IMPORT,
					TT_STRING,
					TT_WORD,
					TT_SEMICOLON,
					TT_NATIVE,
					TT_CLASS,
					TT_SUPER,
					TT_LEFT_BRACE,
					TT_RIGHT_BRACE,
					TT_SCOPE,
					TT_METHOD_CONTENT,
					TT_WORD_SUPER,
					TT_NAMESPACE,
					TT_WORD_FUNCTION,
					TT_WORD_VAR
				};
			}
		}
	}
}

#endif // LIBRARY_DJS_ANALYSIS_TOKENS_TOKENTYPE_H
