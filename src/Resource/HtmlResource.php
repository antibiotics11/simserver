<?php

namespace simserver\Resource;
use InvalidArgumentException;

final class HtmlResource extends Resource {

  public const DEFAULT_LANGUAGE      = "en-US";
  public const DEFAULT_HEAD          = [
    "<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\">",
    "<meta property=\"og:type\" content=\"website\">"
  ];


  private Array         $headItems;
  private String        $body;
  private ?LanguageCode $language;

  public function __construct(bool $setDefault = true) {

    parent::__construct(MimeType::fromName("TYPE_HTML"), "");
  
    $this->headItems   = $setDefault ? self::DEFAULT_HEAD : [];
    $this->body        = "";
    $this->language    = null;
    if ($setDefault) {
      $this->setLanguage(self::DEFAULT_LANGUAGE);
    }

  }

  public function pushHead(String $head): void {
    $this->headItems[] = $head;
  }

  public function setHeadItems(Array $headItems = []): void {
    $this->headItems = $headItems;
  }

  public function getHeadItems(): Array {
    return $this->headItems;
  }

  public function setBody(String $body = ""): void {
    $this->body = $body;
  }

  public function getBody(): String {
    return mb_convert_encoding($this->body, "UTF-8");
  }

  public function setLanguage(LanguageCode | String $language): void {
    if (!$language instanceof LanguageCode) {
      $language = LanguageCode::from($language);
      if ($language === null) {
        throw new InvalidArgumentException("Invalid language code provided.");
      }
    }
    $this->language = $language;
  }

  public function getLanguage(): LanguageCode {
    return $this->language;
  }
  
  public function setTitle(String $title): void {
    $this->pushHead(sprintf("<title>%s</title>", $title));
    $this->pushHead(sprintf("<meta name=\"title\" content=\"%s\">", $title));
    $this->pushHead(sprintf("<meta property=\"og:title\" content=\"%s\">", $title));
  }
  
  public function setDescription(String $description): void {
    $this->pushHead(sprintf("<meta name=\"description\" content=\"%s\">", $description));
    $this->pushHead(sprintf("<meta property=\"og:description\" content=\"%s\">", $description)); 
  }
  
  public function setScript(String $src, bool $inline = false): void {
    if ($inline) {
      $this->pushHead(sprintf("<script type=\"text/javascript\">%s</script>", $src));
    } else {
      $this->pushHead(sprintf("<script type=\"text/javascript\" src=\"%s\"></script>", $src));
    }
  }

  public function render(): String {
    $this->setContent(preg_replace("/\t/", "", 
      sprintf("<!DOCTYPE html><html lang=\"%s\">\n<head>\n%s\n</head>\n<body>\n%s\n</body>\n</html>",
        $this->language->value,
        implode("\n", $this->headItems),
        $this->body
      )
    ));
    return $this->content;
  }

};
