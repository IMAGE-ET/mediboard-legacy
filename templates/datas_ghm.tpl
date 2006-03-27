{literal}
<script type="text/javascript">

function doGHSAction(type) {
  var AddUrl = new Url;
  AddUrl.setModuleAction("dPpmsi", "httpreq_do_ghs_action");
  AddUrl.addParam("type", type);
  AddUrl.requestUpdate(type);
}

</script>
{/literal}

<table class="main">
  <tr>
    <td>
      <table class="form">
        <tr>
          <th class="category" />
          <th class="category">Action</td>
          <th class="category">Résultat</td>
        </tr>
        <tr>
          <td onclick="doGHSAction('AddCM');"><button>Go</button>
          <td>Remplissage des Catégories Majeures</td>
          <td class="text" id="AddCM"></td>
        </tr>
        <tr>
          <td onclick="doGHSAction('AddDiagCM');"><button>Go</button>
          <td>Ajout des diagnostics d'entrée dans les CM</td>
          <td class="text" id="AddDiagCM"></td>
        </tr>
        <tr>
          <td onclick="doGHSAction('AddActes');"><button>Go</button>
          <td>Ajout des actes/diagnostics dans les listes</td>
          <td class="text" id="AddActes"></td>
        </tr>
        <tr>
          <td onclick="doGHSAction('AddGHM');"><button>Go</button>
          <td>Ajout des Groupements Homogènes de Malades</td>
          <td class="text" id="AddGHM"></td>
        </tr>
        <tr>
          <td onclick="doGHSAction('AddCMA');"><button>Go</button>
          <td>Ajout des Complications ou Morbidités Associées</td>
          <td class="text" id="AddCMA"></td>
        </tr>
        <tr>
          <td onclick="doGHSAction('AddIncomp');"><button>Go</button>
          <td>Ajout des incompatibilités DP - CMA</td>
          <td class="text" id="AddIncomp"></td>
        </tr>
        <tr>
          <td onclick="doGHSAction('AddArbre');"><button>Go</button>
          <td>Ajout de l'arbre de décision</td>
          <td class="text" id="AddArbre"></td>
        </tr>
      </table>
    </td>
    <td>
    </td>
  </tr>
</table>