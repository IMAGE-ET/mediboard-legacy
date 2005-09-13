{literal}
<script type='text/javascript' src='lib/dynapi/src/dynapi.js'></script>

<script language="Javascript">

  dynapi.library.setPath('lib/dynapi/src/');
</script>

<script language="Javascript">
  dynapi.library.include('dynapi.api');
  dynapi.library.include('dynapi.library');
  dynapi.library.include('dynapi.debug');
  dynapi.library.include('DragEvent');
</script>

<script language="Javascript">
  var fond = '<img src="modules/dPcabinet/images/tonal.png" />';
  var graph = dynapi.document.addChild(new DynLayer(fond,50,50,466,517));
  var a=graph.addChild(new DynLayer(null,10,10,20,20,'red'))
  DragEvent.enableDragEvents(a);
  DragEvent.setDragBoundary(a, {left:5, right:5, top:5, bottom:5});
  dynapi.onLoad(function() {
    str = '// Try these tests:\n\n'+
    'p.setSize(150,350);\n'+
    '//p.setSize(200,200);\n';
    dynapi.debug.setEvaluate(str);
  });
</script>

<script language="Javascript">
  dynapi.document.insertAllChildren();
</script>

{/literal}