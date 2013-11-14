
The basic page template is base.html.twig.

Each domain inherits from it in the domain specific base templates 
(DataDomain.html.twig, ClassificationDomain.html.twig, etc.)

# Symfony conventions

The logical template name Bundle:Controller:File.extension resolves to 
BundlePath/Resources/views/Controller/File.extension
In the case of DTAMetadataBundle
src/DTA/MetadataBundle/Resources/views/Controller/File.extension
