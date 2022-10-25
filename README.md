# capture-state
## Introduction

Capture et stocke un etat d'une donnée à un instant T.

## Structures 

    SourceGroup: (groupe of sources)
        |-source: (API endpoint)
            |-snapshot (source data snapshoted)

    Comparison:
        |-snapshot (1) (reference data of source)
        |-snapshot (2) (new/comparison data of source)
    
    SourceGroupSnapshot: (Element to aggregate all snapshots of one source groups)
        |-SourceGroup: (source group mentionned at the top)
        |- snapshots:
            |-snapshot (1):
                |- source(1)
            |-snapshot (2):
                |- source(2)
            |-snapshot (...):
                |- source(...)
    
    SourceGroupComparison:
        |- SourceGroupSnapshot(1)
        |- SourceGroupSnapshot(2)

## Some rules
* The site is based on **easy admin** dashboard
* When source API got exception, snapshot can't be created
* When creating single comparison (menu "**Comparison**"), the comparison si not make immediatly. 
You may click on "**Refresh compare data button**" on show comparison page.
