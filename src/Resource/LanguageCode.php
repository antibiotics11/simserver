<?php

namespace simserver\Resource;

enum LanguageCode: String {

  /**
   * Get array of LanguageCode instances that match the given nation name.
   *
   * @param String $nationName  nation name to search for
   * @return Array              array of LanguageCode instances
   */
  public static function getByNationName(String $nationName): Array {

    $nationName = strtoupper(str_replace(" ", "_", trim($nationName)));
    $languageCodes = [];
    foreach (self::cases() as $case) {
      if (str_contains($case->name, $nationName)) {
        $languageCodes[] = $case;
      }
    }
    
    return $languageCodes;

  }


  case AFRIKAANS                                = "af";
  case AFRIKAANS_SOUTH_AFRICA                   = "af-ZA";
  case ARABIC                                   = "ar";
  case ARABIC_UAE                               = "ar-AE";
  case ARABIC_BAHRAIN                           = "ar-BH";
  case ARABIC_ALGERIA                           = "ar-DZ";
  case ARABIC_EGYPT                             = "ar-EG";
  case ARABIC_IRAQ                              = "ar-IQ";
  case ARABIC_JORDAN                            = "ar-JO";
  case ARABIC_KUWAIT                            = "ar-KW";
  case ARABIC_LEBANON                           = "ar-LB";
  case ARABIC_LIBYA                             = "ar-LY";
  case ARABIC_MOROCCO                           = "ar-MA";
  case ARABIC_OMAN                              = "ar-OM";
  case ARABIC_QATAR                             = "ar-QA";
  case ARABIC_SAUDI_ARABIA                      = "ar-SA";
  case ARABIC_SYRIA                             = "ar-SY";
  case ARABIC_TUNISIA                           = "ar-TN";
  case ARABIC_YEMEN                             = "ar-YE";
  case AZERI_LATIN                              = "az";
  case AZERI_LATIN_AZERBAIJAN                   = "az-AZ";
  case AZERI_CYRILLIC_AZERBAIJAN                = "az-AZ";
  case BELARUSIAN                               = "be";
  case BELARUSIAN_BELARUS                       = "be-BY";
  case BULGARIAN                                = "bg";
  case BULGARIAN_BULGARIA                       = "bg-BG";
  case BOSNIAN_BOSNIA_AND_HERZEGOVINA           = "bs-BA";
  case CATALAN                                  = "ca";
  case CATALAN_SPAIN                            = "ca-ES";
  case CZECH                                    = "cs";
  case CZECH_CZECH_REPUBLIC                     = "cs-CZ";
  case WELSH                                    = "cy";
  case WELSH_UNITED_KINGDOM                     = "cy-GB";
  case DANISH                                   = "da";
  case DANISH_DENMARK                           = "da-DK";
  case GERMAN                                   = "de";
  case GERMAN_AUSTRIA                           = "de-AT";
  case GERMAN_SWITZERLAND                       = "de-CH";
  case GERMAN_GERMANY                           = "de-DE";
  case GERMAN_LIECHTENSTEIN                     = "de-LI";
  case GERMAN_LUXEMBOURG                        = "de-LU";
  case DIVEHI                                   = "dv";
  case DIVEHI_MALDIVES                          = "dv-MV";
  case GREEK                                    = "el";
  case GREEK_GREECE                             = "el-GR";
  case ENGLISH                                  = "en";
  case ENGLISH_AUSTRALIA                        = "en-AU";
  case ENGLISH_BELIZE                           = "en-BZ";
  case ENGLISH_CANADA                           = "en-CA";
  case ENGLISH_CARIBBEAN                        = "en-CB";
  case ENGLISH_UNITED_KINGDOM                   = "en-GB";
  case ENGLISH_IRELAND                          = "en-IE";
  case ENGLISH_JAMAICA                          = "en-JM";
  case ENGLISH_NEW_ZEALAND                      = "en-NZ";
  case ENGLISH_REPUBLIC_OF_THE_PHILIPPINES      = "en-PH";
  case ENGLISH_TRINIDAD_AND_TOBAGO              = "en-TT";
  case ENGLISH_UNITED_STATES                    = "en-US";
  case ENGLISH_SOUTH_AFRICA                     = "en-ZA";
  case ENGLISH_ZIMBABWE                         = "en-ZW";
  case ESPERANTO                                = "eo";
  case SPANISH                                  = "es";
  case SPANISH_ARGENTINA                        = "es-AR";
  case SPANISH_BOLIVIA                          = "es-BO";
  case SPANISH_CHILE                            = "es-CL";
  case SPANISH_COLOMBIA                         = "es-CO";
  case SPANISH_COSTA_RICA                       = "es-CR";
  case SPANISH_DOMINICAN_REPUBLIC               = "es-DO";
  case SPANISH_ECUADOR                          = "es-EC";
  case SPANISH_CASTILIAN                        = "es-ES";
  case SPANISH_SPAIN                            = "es-ES";
  case SPANISH_GUATEMALA                        = "es-GT";
  case SPANISH_HONDURAS                         = "es-HN";
  case SPANISH_MEXICO                           = "es-MX";
  case SPANISH_NICARAGUA                        = "es-NI";
  case SPANISH_PANAMA                           = "es-PA";
  case SPANISH_PERU                             = "es-PE";
  case SPANISH_PUERTO_RICO                      = "es-PR";
  case SPANISH_PARAGUAY                         = "es-PY";
  case SPANISH_EL_SALVADOR                      = "es-SV";
  case SPANISH_URUGUAY                          = "es-UY";
  case SPANISH_VENEZUELA                        = "es-VE";
  case ESTONIAN                                 = "et";
  case ESTONIAN_ESTONIA                         = "et-EE";
  case BASQUE                                   = "eu";
  case BASQUE_SPAIN                             = "eu-ES";
  case FARSI                                    = "fa";
  case FARSI_IRAN                               = "fa-IR";
  case FINNISH                                  = "fi";
  case FINNISH_FINLAND                          = "fi-FI";
  case FAROESE                                  = "fo";
  case FAROESE_FAROE_ISLANDS                    = "fo-FO";
  case FRENCH                                   = "fr";
  case FRENCH_BELGIUM                           = "fr-BE";
  case FRENCH_CANADA                            = "fr-CA";
  case FRENCH_SWITZERLAND                       = "fr-CH";
  case FRENCH_FRANCE                            = "fr-FR";
  case FRENCH_LUXEMBOURG                        = "fr-LU";
  case FRENCH_PRINCIPALITY_OF_MONACO            = "fr-MC";
  case GALICIAN                                 = "gl";
  case GALICIAN_SPAIN                           = "gl-ES";
  case GUJARATI                                 = "gu";
  case GUJARATI_INDIA                           = "gu-IN";
  case HEBREW                                   = "he";
  case HEBREW_ISRAEL                            = "he-IL";
  case HINDI                                    = "hi";
  case HINDI_INDIA                              = "hi-IN";
  case CROATIAN                                 = "hr";
  case CROATIAN_BOSNIA_AND_HERZEGOVINA          = "hr-BA";
  case CROATIAN_CROATIA                         = "hr-HR";
  case HUNGARIAN                                = "hu";
  case HUNGARIAN_HUNGARY                        = "hu-HU";
  case ARMENIAN                                 = "hy";
  case ARMENIAN_ARMENIA                         = "hy-AM";
  case INDONESIAN                               = "id";
  case INDONESIAN_INDONESIA                     = "id-ID";
  case ICELANDIC                                = "is";
  case ICELANDIC_ICELAND                        = "is-IS";
  case ITALIAN                                  = "it";
  case ITALIAN_SWITZERLAND                      = "it-CH";
  case ITALIAN_ITALY                            = "it-IT";
  case JAPANESE                                 = "ja";
  case JAPANESE_JAPAN                           = "ja-JP";
  case GEORGIAN                                 = "ka";
  case GEORGIAN_GEORGIA                         = "ka-GE";
  case KAZAKH                                   = "kk";
  case KAZAKH_KAZAKHSTAN                        = "kk-KZ";
  case KANNADA                                  = "kn";
  case KANNADA_INDIA                            = "kn-IN";
  case KOREAN                                   = "ko";
  case KOREAN_KOREA                             = "ko-KR";
  case KONKANI                                  = "kok";
  case KONKANI_INDIA                            = "kok-IN";
  case KYRGYZ                                   = "ky";
  case KYRGYZ_KYRGYZSTAN                        = "ky-KG";
  case LITHUANIAN                               = "lt";
  case LITHUANIAN_LITHUANIA                     = "lt-LT";
  case LATVIAN                                  = "lv";
  case LATVIAN_LATVIA                           = "lv-LV";
  case MAORI                                    = "mi";
  case MAORI_NEW_ZEALAND                        = "mi-NZ";
  case FYRO_MACEDONIAN                          = "mk";
  case FYRO_YUGOSLAV_REPUBLIC_OF_MACEDONIA      = "mk-MK";
  case MONGOLIAN                                = "mn";
  case MONGOLIAN_MONGOLIA                       = "mn-MN";
  case MARATHI                                  = "mr";
  case MARATHI_INDIA                            = "mr-IN";
  case MALAY                                    = "ms";
  case MALAY_BRUNEI_DARUSSALAM                  = "ms-BN";
  case MALAY_MALAYSIA                           = "ms-MY";
  case MALTESE                                  = "mt";
  case MALTESE_MALTA                            = "mt-MT";
  case NORWEGIAN_BOKMÅL                         = "nb";
  case NORWEGIAN_BOKMÅL_NORWAY                  = "nb-NO";
  case DUTCH                                    = "nl";
  case DUTCH_BELGIUM                            = "nl-BE";
  case DUTCH_NETHERLANDS                        = "nl-NL";
  case NORWEGIAN_NYNORSK_NORWAY                 = "nn-NO";
  case NORTHERN_SOTHO                           = "ns";
  case NORTHERN_SOTHO_SOUTH_AFRICA              = "ns-ZA";
  case PUNJABI                                  = "pa";
  case PUNJABI_INDIA                            = "pa-IN";
  case POLISH                                   = "pl";
  case POLISH_POLAND                            = "pl-PL";
  case PASHTO                                   = "ps";
  case PASHTO_AFGHANISTAN                       = "ps-AR";
  case PORTUGUESE                               = "pt";
  case PORTUGUESE_BRAZIL                        = "pt-BR";
  case PORTUGUESE_PORTUGAL                      = "pt-PT";
  case QUECHUA                                  = "qu";
  case QUECHUA_BOLIVIA                          = "qu-BO";
  case QUECHUA_ECUADOR                          = "qu-EC";
  case QUECHUA_PERU                             = "qu-PE";
  case ROMANIAN                                 = "ro";
  case ROMANIAN_ROMANIA                         = "ro-RO";
  case RUSSIAN                                  = "ru";
  case RUSSIAN_RUSSIA                           = "ru-RU";
  case SANSKRIT                                 = "sa";
  case SANSKRIT_INDIA                           = "sa-IN";
  case SAMI_NORTHERN                            = "se";
  case SAMI_NORTHERN_FINLAND                    = "se-FI";
  case SAMI_SKOLT_FINLAND                       = "se-FI";
  case SAMI_INARI_FINLAND                       = "se-FI";
  case SAMI_NORTHERN_NORWAY                     = "se-NO";
  case SAMI_LULE_NORWAY                         = "se-NO";
  case SAMI_SOUTHERN_NORWAY                     = "se-NO";
  case SAMI_NORTHERN_SWEDEN                     = "se-SE";
  case SAMI_LULE_SWEDEN                         = "se-SE";
  case SAMI_SOUTHERN_SWEDEN                     = "se-SE";
  case SLOVAK                                   = "sk";
  case SLOVAK_SLOVAKIA                          = "sk-SK";
  case SLOVENIAN                                = "sl";
  case SLOVENIAN_SLOVENIA                       = "sl-SI";
  case ALBANIAN                                 = "sq";
  case ALBANIAN_ALBANIA                         = "sq-AL";
  case SERBIAN_LATIN_BOSNIA_AND_HERZEGOVINA     = "sr-BA";
  case SERBIAN_CYRILLIC_BOSNIA_AND_HERZEGOVINA  = "sr-BA";
  case SERBIAN_LATIN_SERBIA_AND_MONTENEGRO      = "sr-SP";
  case SERBIAN_CYRILLIC_SERBIA_AND_MONTENEGRO   = "sr-SP";
  case SWEDISH                                  = "sv";
  case SWEDISH_FINLAND                          = "sv-FI";
  case SWEDISH_SWEDEN                           = "sv-SE";
  case SWAHILI                                  = "sw";
  case SWAHILI_KENYA                            = "sw-KE";
  case SYRIAC                                   = "syr";
  case SYRIAC_SYRIA                             = "syr-SY";
  case TAMIL                                    = "ta";
  case TAMIL_INDIA                              = "ta-IN";
  case TELUGU                                   = "te";
  case TELUGU_INDIA                             = "te-IN";
  case THAI                                     = "th";
  case THAI_THAILAND                            = "th-TH";
  case TAGALOG                                  = "tl";
  case TAGALOG_PHILIPPINES                      = "tl-PH";
  case TSWANA                                   = "tn";
  case TSWANA_SOUTH_AFRICA                      = "tn-ZA";
  case TURKISH                                  = "tr";
  case TURKISH_TURKEY                           = "tr-TR";
  case TATAR                                    = "tt";
  case TATAR_RUSSIA                             = "tt-RU";
  case TSONGA                                   = "ts";
  case UKRAINIAN                                = "uk";
  case UKRAINIAN_UKRAINE                        = "uk-UA";
  case URDU                                     = "ur";
  case URDU_ISLAMIC_REPUBLIC_OF_PAKISTAN        = "ur-PK";
  case UZBEK_LATIN                              = "uz";
  case UZBEK_LATIN_UZBEKISTAN                   = "uz-UZ";
  case UZBEK_CYRILLIC_UZBEKISTAN                = "uz-UZ";
  case VIETNAMESE                               = "vi";
  case VIETNAMESE_VIETNAM                       = "vi-VN";
  case XHOSA                                    = "xh";
  case XHOSA_SOUTH_AFRICA                       = "xh-ZA";
  case CHINESE                                  = "zh";
  case CHINESE_S                                = "zh-CN";
  case CHINESE_HONGKONG                         = "zh-HK";
  case CHINESE_MACAU                            = "zh-MO";
  case CHINESE_SINGAPORE                        = "zh-SG";
  case CHINESE_T                                = "zh-TW";
  case ZULU                                     = "zu";
  case ZULU_SOUTH_AFRICA                        = "zu-ZA";

}

